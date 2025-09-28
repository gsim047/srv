# This file was created by MkExe ver. %VER% [%DATE% %TIME%]

NAME=%NAME%
MKFILE=$(NAME).gmk

CC=g++
#RCC=windres
RC=$(NAME).rc
CFG=$(NAME).cfg

ifeq ($(OS),Windows_NT)

UNAME_M=x86_64
TARGET=$(NAME).exe
RES=$(NAME)_res.o
MYBIN=C:\Tools
#QTPATH=/Qt/mingw
#QTTOOLS=/Qt/tools
#CPPFLAGS=-s -I /include -I $(QTPATH)/include -I $(QTPATH)/include/QtCore -I $(QTPATH)/include/QtNetwork -std=c++11
CPPFLAGS=-s -I /include -std=c++11
# -z noexecstack
CFLAGS=$(CPPFLAGS)
#LIBS=-L /lib -L $(QTPATH)/lib -lcMain -ludk -lpthread -lversion -lQt5Core -lQt5Network
LIBS=-L /lib %LIB%
#RCCCMD=$(RCC) -o $(RES) $(RC)
RM_S=del /Q /S
CP_S=xcopy /Y
CP_SU=$(CP_S)

else

UNAME_M = $(shell uname -m)

TARGET=$(NAME)
RES=$(NAME).res
MYBIN=~/bin
#QTLIB=/usr/lib/x86_64-linux-gnu/
#QTINC=/usr/include/x86_64-linux-gnu/qt6/
#QTTOOLS=/Qt/tools
#CPPFLAGS=-s -fPIC -I ~/include -I $(QTINC) -I $(QTINC)/QtCore -I $(QTINC)/QtNetwork -std=c++17
CPPFLAGS=-s -fPIC -I ~/include -std=c++17 -z noexecstack
CFLAGS=$(CPPFLAGS)
#LIBS=-L ~/lib -L $(QTLIB) -lcMain -ludk -lpthread -lQt6Core -lQt6Network
LIBS=-L ~/lib %LIB%
RM_S=rm -f
CP_S=cp -v -u
CP_SU=sudo $(CP_S)

endif

ifeq ($(UNAME_M),x86_64)
RCCCMD=objcopy -I binary -O elf64-x86-64 -B i386 $(RC) $(RES)
else
#RCCCMD=objcopy -I binary -O elf32-i386 -B i386 $(RC) $(RES)
RCCCMD=objcopy -I binary -O elf64-littleaarch64 $(RC) $(RES)
endif

ARC=$(NAME)_src
LLIBS=%LLIB%

MM=$(NAME).d

OBJS=%OBJ%
#	$(NAME).o


all: $(TARGET) $(OBJS) %ARC% $(ARC).rar

$(TARGET) : $(OBJS) $(RES) $(LLIBS) $(MKFILE)
	$(CC) $(CFLAGS) -o $(TARGET) $(OBJS) $(RES) $(LIBS) $(LLIBS)

#include $(MM)

%.o : %.cpp
	$(CC) $(CPPFLAGS) -c -o $@ $<

%.o : %.c
	$(CC) $(CFLAGS) -c -o $@ $<


$(RES) : $(RC) $(OBJS) $(MKFILE)
	vinc $(RC)
	$(RCCCMD)

$(ARC).rar : $(OBJS) $(MKFILE)
	7z u $(ARC) *.h *.cpp *.c *.rc *.mak *.def *.gmk *.ico
# cp $(ARC).rar \Doc\h

clean:
	$(RM_S) $(TARGET)
	$(RM_S) $(OBJS)
	$(RM_S) $(RES)


install: all copy

copy: all
	$(CP_SU) $(TARGET) $(MYBIN)
	$(CP_SU) $(CFG) $(MYBIN)


#$(MM): *.cpp *.h $(MKFILE)
#	$(CC) $(CFLAGS) -MM *.cpp *.h > $(MM)
