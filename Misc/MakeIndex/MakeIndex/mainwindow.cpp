#include "mainwindow.h"
#include "ui_mainwindow.h"

#include "mywidget.h"
#include <QImage>
#include <QInputDialog>
#include <QProgressDialog>
#include <QFileDialog>

#include <QDebug>

MainWindow::MainWindow(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::MainWindow),
    _img(NULL)
{
    ui->setupUi(this);
    _mutex = new QMutex() ;
    _widget = new MyWidget(_mutex) ;
    _dialog = new QProgressDialog(tr("Computing Map"), tr("abort"), 0, 0, this) ;
    this->setCentralWidget(_widget);

    _timer = new QTimer() ;
    _timer->setInterval(250);

    connect(_timer, SIGNAL(timeout()), _widget, SLOT(update())) ;
    connect(ui->actionNew, SIGNAL(triggered()), this, SLOT(onNewMap())) ;

    _timer->start() ;

}

MainWindow::~MainWindow()
{
    delete ui;
    delete _img ;
    delete _dialog ;
    delete _timer ;
}

void MainWindow::onNewMap() {

    qDebug() << "onNewMap()" ;

    bool ok ;
    int width  = QInputDialog::getInt(this, "Largeur", "Largeur de la carte", 1440, 90, 2880, 30, &ok) ;
    if (! ok) { return ; }
    qDebug() << " - Width : " << width ;

    int height = QInputDialog::getInt(this, "Largeur", "Largeur de la carte", width / 2, 90, 2880, 30, &ok) ;
    if (! ok) { return ; }
    qDebug() << " - Height " << height ;

    QString filename = QFileDialog::getOpenFileName(this, tr("Villes"), "C:\\Users\\thiba_000\\svn\\luchronia\\trunk\\php\\cmd", "*.txt") ;
    if (filename.isEmpty()) { return ; }
    qDebug() << " - File " << filename ;

    delete _img ;
    _img = new QImage(width, height, QImage::Format_ARGB32) ;
    _mutex->lock();
    _img->fill(Qt::blue) ;
    _mutex->unlock();
    _widget->setImage(_img);

    qDebug() << " - Image set" ;

    delete _worker ;
    _worker = new MyWorker(_img, _dialog, filename, _mutex) ;
    qDebug() << " - Worker created" ;

    connect(_worker, SIGNAL(newMax(int)), this, SLOT(onNewMax(int))) ;
    connect(_worker, SIGNAL(newValue(int)), this, SLOT(onNewValue(int))) ;
    connect(_worker, SIGNAL(done()), this, SLOT(onDone())) ;
    qDebug() << " - Signal / Slot done" ;

    _dialog->setMaximum(_img->width() * _img->height());
    _dialog->show() ;
    qDebug() << " - Dialog Shown" ;

    _worker->start();
    qDebug() << " - worker started" ;
}

void MainWindow::onSaveMap() {

}

void MainWindow::onNewValue(int v) {
    _dialog->setValue(v);
}

void MainWindow::onNewMax(int m) {
    _dialog->setMaximum(m);
}

void MainWindow::onDone() {
    QString filename = QFileDialog::getSaveFileName(this, tr("Villes"), "C:\\Users\\thiba_000\\svn\\luchronia\\trunk\\php\\cmd", "*.txt") ;
    if (filename.isEmpty()) { return ; }
    qDebug() << " - File " << filename ;

    _worker->writeToFile(filename);
}
