#!/bin/bash

BASEDIR=$(dirname $0)

ip=`cat $BASEDIR/nmap.su.ip`

nmap -sU --script snmp-interfaces,nbstat.nse -p137,161 $ip/24 > $BASEDIR/$ip.nmap
chmod 666 $BASEDIR/$ip.nmap
