cd /d/temp
rm -rf PSI_Help
git clone https://gitee.com/jtbb/jt_psi/PSI_Help.git
cd PSI_Help && gitbook install && gitbook build
