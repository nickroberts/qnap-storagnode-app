#!/bin/sh
CONF=/etc/config/qpkg.conf
QPKG_NAME="UTLABS"
LOGFILE=/var/log/${QPKG_NAME}
QPKG_ROOT=`/sbin/getcfg $QPKG_NAME Install_Path -f ${CONF}`
APACHE_ROOT=`/sbin/getcfg SHARE_DEF defWeb -d Qweb -f /etc/config/def_share.info`
export QNAP_QPKG=$QPKG_NAME

# Function to get formatted date
# Example: 2006-08-14T02:34:56-06:00
get_date () {
  return date --iso-8601=seconds
}

case "$1" in
  start)
    echo `get_date()` " UTLABS : Request to start ($@) " >> $LOGFILE
    ENABLED=$(/sbin/getcfg $QPKG_NAME Enable -u -d FALSE -f $CONF)
    if [ "$ENABLED" != "TRUE" ]; then
        echo "$QPKG_NAME is disabled."
	      echo `get_date()` " UTLABS : $PKG_NAME is disabled " >> $LOGFILE
        exit 1
    fi
    : ADD START ACTIONS HERE
    echo `get_date()` "Create $QPKG_ROOT/web folder link to UTLABS folder " >> /tmp/runUTLABS.log
    ln -s $QPKG_ROOT/web.new /home/Qhttpd/Web/UTLABS
    chmod -Rf 777 /home/Qhttpd/Web/UTLABS
    echo `get_date()` " UTLABS : Request to start ($@) completed " >> $LOGFILE
    ;;

  stop)
    echo `get_date()` " UTLABS : Request to stop ($@) " >> $LOGFILE
    : ADD STOP ACTIONS HERE
    rm /home/Qhttpd/Web/UTLABS
    ;;

  restart)
    echo `get_date()` " UTLABS : Request restart ($@) " >> $LOGFILE
    $0 stop
    $0 start
    ;;

  *)
    echo `get_date()` " UTLABS : Request UNKNOWN ($@) " >> $LOGFILE
    echo "Usage: $0 {start|stop|restart}"
    exit 1
esac

exit 0
