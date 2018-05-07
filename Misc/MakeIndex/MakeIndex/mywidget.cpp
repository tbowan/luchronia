#include "mywidget.h"

#include <QPainter>
#include <QDebug>
#include <QMutexLocker>

MyWidget::MyWidget(QMutex *mut) : _mutex(mut), _img(NULL)
{

}

MyWidget::~MyWidget()
{

}

void MyWidget::setImage(QImage * img) {
    _img = img ;
}

void MyWidget::paintEvent(QPaintEvent *) {
    if (_img) {
        QMutexLocker locker(_mutex) ;
        QPainter p(this) ;
        p.drawImage(rect(), *_img) ;
    }
}
