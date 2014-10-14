REM This will generate the zipfiles for ACT Parse in /build/packages
REM This needs the zip binaries from Info-Zip installed. An installer can be found http://gnuwin32.sourceforge.net/packages/zip.htm
setlocal
SET PATH=%PATH%;C:\Program Files (x86)\GnuWin32\bin
rmdir /q /s packages
mkdir packages
REM Component
cd ../com_actparse/
zip -r ../build/packages/com_actparse.zip *
REM Modules
cd ../mod_actparse/
zip -r ../build/packages/mod_actparse.zip *
REM Plugins
cd ../plg_search_actparse/
zip -r ../build/packages/plg_search_actparse.zip *
REM Package
cd ../build/packages/
copy ..\..\pkg_actparse.xml
zip pkg_actparse.zip *
del pkg_actparse.xml
