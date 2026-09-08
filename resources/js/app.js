import "bootstrap";
import "leaflet-rotatedmarker";

import { Geolocation } from '@capacitor/geolocation';
import { Capacitor } from '@capacitor/core';

window.Capacitor = Capacitor;
window.CapacitorGeolocation = Geolocation;

import { CapacitorWifi } from '@capgo/capacitor-wifi';
window.CapacitorWifiNetwork = CapacitorWifi;


import { Motion } from '@capacitor/motion';
window.CapacitorMotion = Motion;

import { CapgoCompass } from '@capgo/capacitor-compass';
window.CapacitorCompass = CapgoCompass;

