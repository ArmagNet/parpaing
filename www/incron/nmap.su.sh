#!/bin/bash

BASEDIR=$(dirname $0)

ip=`cat $BASEDIR/nmap.su.ip`

nmap -sU --script nbstat.nse -p137 $ip/24 > $BASEDIR/$ip.nmap
chmod 666 $BASEDIR/$ip.nmap
