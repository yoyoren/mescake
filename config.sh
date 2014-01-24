yum install libxml2
yum install libxml2-devel
yum install libpng-devel

./configure --prefix=/usr/local/php --enable-fpm --sysconfdir=/etc --datadir=/usr/share --includedir=/usr/include --libdir=/usr/lib --libexecdir=/usr/libexec --localstatedir=/var --sharedstatedir=/var/lib   --with-config-file-path=/etc --with-config-file-scan-dir=/etc/php.d 