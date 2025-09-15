@echo off
SETLOCAL EnableDelayedExpansion
set /A num=0
for /F "tokens=*" %%i in ('dir /b *.jpg') do (
	if not '%%i'=="%~n0%~x0" (
		set /A num+=1
		ren "%%i" "!num!%.!avif"
	)
)
endlocal
exit
