#-------------------------------------------------
#
# Project created by QtCreator 2015-05-04T10:46:52
#
#-------------------------------------------------

QT       += core gui sql

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = MakeIndex
TEMPLATE = app


SOURCES += main.cpp\
        mainwindow.cpp \
    mywidget.cpp \
    myworker.cpp

HEADERS  += mainwindow.h \
    mywidget.h \
    myworker.h

FORMS    += mainwindow.ui
