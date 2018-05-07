#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>

#include <QImage>
#include <QTimer>
#include "mywidget.h"
#include "myworker.h"
#include <QMutex>

namespace Ui {
class MainWindow;
}

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();

private:
    Ui::MainWindow  * ui;
    QImage          * _img ;
    MyWidget        * _widget ;
    MyWorker        * _worker ;
    QProgressDialog * _dialog ;
    QTimer          * _timer ;
    QMutex          * _mutex ;

public slots:

    void onNewMap() ;
    void onSaveMap() ;

    void onNewValue(int v) ;
    void onNewMax(int m) ;
    void onDone() ;
};

#endif // MAINWINDOW_H
