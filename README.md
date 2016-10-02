# Read and Store Tilt Digital Hydrometer Readings using a Raspberry Pi

The [Tilt Digital Hydrometer](http://tilthydrometer.com/products/brewometer) is a small device utilizing a [PunchThrough Bean](https://punchthrough.com/bean).

The tilt utilizes the on-board accelerometer on the bean to measure the tilt of the Tilt in wort and broadcasts the result as Specific Gravity (SG) as part of an iBeacon advertisement broadcast.

Tilt provides apps in iOS and Android that can read the iBeacon and display it, as well as store the data in the cloud.

This software utilizes a Raspberry Pi (RPi), or similar device, to capture the iBeacon advertisements and store the data locally in a MySQL database. It also provides code that will display a simple local webpage that has a real-time graph of the fermentation progress.

#Initial Setup

You should have some experience setting up a Linux distribution, and know how to set permissions, read logs, and troubleshoot. If you are a complete noob at these items, it may be worth your while to get a more experienced friend to assist you.

The RPi ideally should be a Model 3, as it already has Bluetooth Low Energy (BLE) hardware on the board. Other boards, such as the RPI Model 2, Odroid C2, etc., may work if a BLE dongle is added to a USB port.

The RPi should be loaded with the latest version of [Raspbian](https://www.raspberrypi.org/downloads/raspbian/). Whether you choose to use the light or GUI version is a personal choice. I recommend at least an 8GB SD card. I would also setup your visudo to allow you to run programs with elevated privileges without constantly entering a password.

The RPi should be setup as a [LAMP server](https://www.element14.com/community/community/raspberry-pi/raspberrypi_projects/blog/2014/02/24/raspberry-pi-as-a-lamp-server), as well as the latest versions of bluez, python-bluez, bluetooth.

Once the RPi is setup and running, download abd install the code from this github to your webserver directory. It's typically /var/www/html, but YMMV.

Next, place your Tilt in a container of water large enough to allow the Tilt to float freely.
From a terminal, enter `sudo php TiltPythonFind.php`.
You should get a response that shows the MAC address of the Tilt, the current Specfic Gravity (SG) reading, and the Temperature.

Using a text editor, copy the MAC address that was displayed, and place it into the `$tiltStr` variable in `TiltPythonMySQLcrontab.php`. You may also want to make sure that your apache website directory is correct as well by checking `$htmlAddrStr` in the same file.

Finally you'll create a crontab entry to run `TiltPythonMySQLcrontab.php` regularly. I recommend once a minute. I also recommend you run it as a root or superuser crontab to help avoid permision problems.
