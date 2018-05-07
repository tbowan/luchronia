#ifndef MYWORKER_H
#define MYWORKER_H

#include <QThread>
#include <QProgressDialog>
#include <QMutex>
#include <QRgb>

class MyWorker : public QThread
{
    Q_OBJECT

signals :
    void newValue(int v) ;
    void newMax(int m) ;
    void done() ;

private:
    QImage          *_img ;
    QProgressDialog * _dialog ;
    QString           _filename ;
    QMutex          * _mutex ;

    int               _w ;
    int               _h ;
    double          * _dist ;
    int             * _ids ;
    int               _dy ;
    int             * _dx ;

public:
    MyWorker(QImage *img, QProgressDialog *dialog, QString filename, QMutex * mut);
    ~MyWorker();

    int getId(int i, int j) ;
    void setId(int i, int j, int id) ;

    double getDist(int i, int j) ;
    void setDist(int i, int j, double dist) ;

    void setPixel(int x, int y, int id, double lat, double lon, int albedo) ;
    void addCity(int id, double lat, double lon, int albedo, int n) ;

    void run() ;
    void initDs(int n) ;
    double dist(double x1, double y1, double x2, double y2) ;

    void writeToFile(QString filename) ;
};

#endif // MYWORKER_H
