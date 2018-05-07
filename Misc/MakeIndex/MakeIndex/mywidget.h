#ifndef MYWIDGET_H
#define MYWIDGET_H

#include <QWidget>
#include <QImage>
#include <QPaintEvent>
#include <QMutex>

class MyWidget : public QWidget
{
private:
    QImage * _img ;
    QMutex * _mutex ;

public:
    MyWidget(QMutex * mut);
    ~MyWidget();

    void setImage(QImage *img) ;

    void paintEvent(QPaintEvent *) ;
};

#endif // MYWIDGET_H
