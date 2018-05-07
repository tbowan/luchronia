#include "myworker.h"

#include <QProgressDialog>
#include <QFile>
#include <QTextStream>
#include <QDebug>
#include <QtGlobal>
#include <QtMath>
#include <QFileDialog>

MyWorker::MyWorker(QImage * img, QProgressDialog * dialog, QString filename, QMutex *mut)
    : _img(img), _dialog(dialog), _filename(filename), _mutex(mut)
{
    _w = _img->width() ;
    _h = _img->height() ;

    _dist = new double[_w * _h] ;
    _ids  = new int[_w * _h] ;
    _dx   = new int[_h] ;
    for (int i = 0; i < _w * _h ; i++) {
        _dist[i] = 0.0 ;
        _ids[i]  = 0   ;
    }

    qDebug() << getId(0, 0) ;
}

MyWorker::~MyWorker()
{
    delete[] _dist ;
    delete[] _ids ;
    delete[] _dx ;
}

int MyWorker::getId(int i, int j) {
    return _ids[i * _h + j] ;
}

void MyWorker::setId(int i, int j, int id) {
    _ids[i * _h + j] = id ;
}

double MyWorker::getDist(int i, int j) {
    return _dist[i * _h + j] ;
}

void MyWorker::setDist(int i, int j, double dist) {
    _dist[i * _h + j] = dist ;
}

double MyWorker::dist(double x1, double y1, double x2, double y2) {
    // angles in radians
    x1 = qDegreesToRadians(x1) ;
    y1 = qDegreesToRadians(y1) ;
    x2 = qDegreesToRadians(x2) ;
    y2 = qDegreesToRadians(y2) ;

    // arcos of spheric arc
    double arccos = qSin(y1) * qSin(y2) + qCos(y1) * qCos(y2) * qCos(x1 - x2) ;

    return qAcos(arccos) ;
}

void MyWorker::setPixel(int x, int y, int id, double lat, double lon, int albedo) {

    if (y < 0 || y >= _h) {
        return ;
    }

    x = (_w + x) % _w ;

    double xlon =      360 * (double) x / (double) _w - 180 ;
    double ylat = 90 - 180 * (double) y / (double) _h ;
    double d = dist(xlon, ylat, lon, lat) ;

    double dp = getDist(x, y) ;
    if (dp == 0 || dp > d) {
        // change city
        setId(x, y, id) ;
        setDist(x, y, d) ;

        // change color
        QMutexLocker locker(_mutex) ;
        _img->setPixel(x, y, QColor(albedo, albedo, albedo).rgba()) ;
    }


}

void MyWorker::addCity(int id, double lat, double lon, int albedo, int n) {

    // lat / long to img coordinate
    int x = qMin(_w - 1, qRound(_w * ((180 + lon) / 360))) ;
    int y = qMin(_h - 1, qRound(_h * (( 90 - lat) / 180))) ;

    for (int j = qMax(0, y - _dy); j < qMin(_h, y + _dy); j++) {
        for (int i = x - _dx[j]; i <= x + _dx[j]; i++) {
            setPixel(i, j, id, lat, lon, albedo) ;
        }
    }

    return ;
}


void MyWorker::initDs(int n) {
    _dy = qCeil(_h / (3.0 * n)) ;
    int t = _h / 3 ;

    for (int i=0; i < t ; i++) {
        double temp   = (double) i / (double) t ;
        double cities = 5.0 * n * temp + 1 ;
        int dx = qCeil(_w / cities) ;
        _dx[i] = dx ;
        _dx[_h - i - 1] = dx ;
    }
    for (int i =  _h / 3.0 ; i < 2.0 * _h / 3.0; i++) {
        _dx[i] = qCeil(_w / (5.0 * n)) ;
    }

}

void MyWorker::run() {
    int w = _img->width() ;
    int h = _img->height() ;

    QFile file(_filename) ;

    if (! file.open(QIODevice::ReadOnly | QIODevice::Text)) {
        return ;
    }

    int cityCount ;
    int n ;
    int id ;
    double lat ;
    double lon ;
    int albedo ;

    QTextStream stream(&file) ;
    stream >> cityCount ;
    stream >> n ;

    initDs(n);

    emit newMax(cityCount) ;

    int cnt = 0 ;
    while (! stream.atEnd()) {
        stream >> id >> lat >> lon >> albedo ;
        addCity(id, lat, lon, albedo, n);
        cnt++ ;
        if (cnt % 100 == 0) {
            emit newValue(cnt) ;
            qDebug() << cnt << " / " << cityCount ;
        }
    }

    emit newValue(cityCount) ;
    emit done() ;
}

void MyWorker::writeToFile(QString filename) {

    QFile outfile(filename) ;
    if (! outfile.open(QIODevice::WriteOnly | QIODevice::Text)) { return ; }

    QTextStream output(&outfile) ;
    output << _w << " " << _h ;
    for (int j=0; j < _h; j++) {
        for (int i=0; i < _w ; i++) {
            output << " " << getId(i, j) ;
        }
    }
    output << "\n" ;

}
