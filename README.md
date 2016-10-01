# Read and Store Tilt Digital Hydrometer Readings using a Raspberry Pi

The [Tilt Digital Hydrometer](http://tilthydrometer.com/products/brewometer) is a small device utilizing a [PunchThrough Bean](https://punchthrough.com/bean).

The tilt utilizes the on-board accelerometer on the bean to measure the tilt of the Tilt in wort and broadcasts the result as Specific Gravity (SG) as part of an iBeacon advertisement broadcast.

Tilt provides apps in iOS and Android that can read the iBeacon and display it, as well as store the data in the cloud.

This software utilizes a Raspberry Pi, or similar device, to capture the iBeacon advertisements and store the data locally in a MySQL database. It also provides code that will display a simple local webpage that has a real-time graph of the fermentation progress.
