@echo off
echo 歡迎使用逢甲大學 Windows KMS 金鑰啟動程式 v0413.2018...
echo 相關資訊可以連結到校園軟體服務網查看(http://software-bank.fcu.edu.tw)
echo 正在指定逢甲大學 Windows KMS 金鑰管理伺服器 ...

:server1
echo 啟動 Windows 作業系統 ...
%SystemRoot%\system32\slmgr.vbs -skms 140.134.205.101:1688
%SystemRoot%\system32\slmgr.vbs -ato
if errorlevel 0 goto end
goto server2

:server2
echo 啟用 Windows 作業系統失敗，正嘗試第二伺服器
%SystemRoot%\system32\slmgr.vbs -skms 140.134.205.101:1689
%SystemRoot%\system32\slmgr.vbs -ato
if errorlevel 0 goto end

:end
echo.