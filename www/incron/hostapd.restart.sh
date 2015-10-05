#!/bin/bash

cp $1 /etc/hostapd/hostapd.conf
/etc/init.d/hostapd restart