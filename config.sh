yum install libxml2
yum install libxml2-devel
yum install libpng-devel

./configure --prefix=/usr/local/php --enable-fpm --sysconfdir=/etc --datadir=/usr/share --includedir=/usr/include --libdir=/usr/lib --libexecdir=/usr/libexec --localstatedir=/var --sharedstatedir=/var/lib   --with-config-file-path=/etc --with-config-file-scan-dir=/etc/php.d --with-pic --disable-rpath --without-pear --with-bz2   --enable-gd-native-ttf  --with-gettext --with-gmp --with-iconv    --with-zlib --with-layout=GNU --enable-exif --enable-ftp --enable-magic-quotes --enable-sockets --enable-sysvsem --enable-sysvshm --enable-sysvmsg --with-kerberos --enable-ucd-snmp-hack --enable-shmop --enable-calendar  --enable-xml --with-system-tzdata  --without-mysql --without-gd --disable-dom --disable-dba --without-unixODBC --disable-pdo --without-sqlite3 --disable-phar --disable-fileinfo  --without-pspell --disable-wddx --without-curl --disable-posix --disable-sysvmsg --disable-sysvshm --disable-sysvsem
