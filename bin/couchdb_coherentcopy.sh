#!/bin/bash

COUCHDBFILE=$1
DESTINATION=$2
LVMDIR=/tmp/lvmtmp

if ! test "$1" || ! test "$2" ; then
    echo "Usage: $0 <COUCHDBFILE> <DESTINATION>" > /dev/stderr
    exit 1
fi

DF=$(sudo df $COUCHDBFILE | tail -n 1  | sed 's/ .*//')
MOUNTING=$(sudo df $COUCHDBFILE | tail -n 1  | awk '{print $6}')

LVMPARTITION=$(sudo lvdisplay $DF | grep 'LV Path' | awk '{print $3}')
LVMGROUP=$(sudo lvdisplay $DF | grep 'VG Name' | awk '{print $3}')

if ! test "$LVMPARTITION"; then
    echo "ERROR: $COUCHDBFILE is not hosted on an LVM partition" > /dev/stderr
    exit 2
fi

RELATIVECOUCHDBFILE=$(echo $COUCHDBFILE | sed 's|'$MOUNTING'||')

sudo lvcreate -s -n snap_couchdb -L 1G $LVMPARTITION | grep -v created

if ! sudo lvdisplay "/dev/"$LVMGROUP"/snap_couchdb"  | grep 'LV Path' > /dev/null ; then
    echo "ERROR: Could not create the snapshot partition" > /dev/stderr
    exit 3;
fi

mkdir -p $LVMDIR

if ! test -e $LVMDIR; then
    echo "ERROR: $LVMDIR not created" > /dev/stderr
    exit 4;
fi

sudo mount  "/dev/"$LVMGROUP"/snap_couchdb" $LVMDIR

sudo rsync -a $LVMDIR"/"$RELATIVECOUCHDBFILE $DESTINATION

sudo umount $LVMDIR
sudo lvremove -f "/dev/"$LVMGROUP"/snap_couchdb" | grep -v successfully

