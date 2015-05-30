' Please do not execute this file directly,
' use setup.bat instead!

wscript.echo "pgwatch Cybertec PostgreSQL Enterprise Monitor Setup"
wscript.echo ""

dim hostname
wscript.stdOut.write "Please enter the host name of the application" & vbCRLF & "(localhost): "
hostname = wscript.stdin.readline
If hostname = "" Then hostname="localhost"
wscript.echo "Host name will be " & hostname
wscript.echo ""

Dim ofso
Set ofso = CreateObject("Scripting.FileSystemObject")
Dim folder, deffolder
Dim fullpath
fullpath = oFSO.GetParentFolderName(Wscript.ScriptFullName)
deffolder = ofso.GetFileName(fullpath)
wscript.stdOut.write "Please enter the folder relative to web root this app is located in" & vbCRLF & "(" & deffolder & "): "
folder = wscript.stdin.readline
If folder = "" Then folder=deffolder
wscript.echo "Folder will be " & folder
wscript.echo ""

Function IsValue(obj)
    ' Check whether a value has been returned.
    Dim tmp
    On Error Resume Next
    tmp = " " & obj
    If Err <> 0 Then
        IsValue = False
    Else
        IsValue = True
    End If
    On Error GoTo 0
End Function

Function execute(msg, cmd)
	wscript.echo msg
	rem wscript.echo "Executing:" & vbCRLF
	wscript.echo cmd
	Dim ret
	Set ret = CreateObject("WScript.Shell").Exec(cmd)
	Do While ret.status = 0
		wscript.sleep 10
		Do
			str=ret.stdOut.read(1)
			wscript.stdOut.write str
		Loop Until str=""
		Do
			str=ret.stdErr.read(1)
			wscript.stdOut.write str
		Loop Until str=""
	Loop
	wscript.echo ""
End function

Dim bins
bins=""
Dim dlg
Set dlg= CreateObject( "Shell.Application" )
Do
	wscript.echo ""
	wscript.echo "Folder selector window popped up."
	wscript.echo "Select PostgreSQL binaries folder" & vbCRLF & "(C:\Program Files\PostgreSQL\9.1\bin\ or so)"
	Set bins = dlg.BrowseForFolder(0, "Select PostgreSQL binaries folder (C:\Program Files\PostgreSQL\9.1\bin\ or so):", &H10, 17)
	If IsValue(bins) then
		bins_valid = bins.Title
	Else
		bins_valid = False
		wscript.echo "Setup terminated"
		wscript.quit(1)
	End if
	On Error Resume next
	bins = bins.parentFolder.ParseName(bins.Title).Path
	wscript.echo bins & "\psql.exe"
Loop Until CreateObject("Scripting.FileSystemObject").FileExists(bins & "\psql.exe")
wscript.echo "Binaries will be used in " & bins
wscript.echo ""

dim dbhost
wscript.stdOut.write "Database server host (localhost): "
dbhost = wscript.stdin.readline
If dbhost = "" Then dbhost="localhost"
wscript.echo "Database connection will go to " & dbhost
wscript.echo ""

dim dbport
wscript.stdOut.write "Database server port (5432): "
dbport = wscript.stdin.readline
If dbport = "" Then dbport="5432"
wscript.echo "Connection will work through port " & dbport
wscript.echo ""

dim dbname
wscript.stdOut.write "Please enter the database name to create (pgwatch): "
dbname = wscript.stdin.readline
If dbname = "" Then dbname="pgwatch"
wscript.echo "Database name will be " & dbname
wscript.echo ""

dim dbpass
wscript.stdOut.write "Password for database user: "
dbpass = wscript.stdin.readline
wscript.echo ""

dim createuser
wscript.stdOut.write "Create a new user to connect by (Y/n): "
createuser = Mid(LCase(wscript.stdin.readline),1,1)
If createuser = "" Or createuser = "y" Then
	createuser = true
	wscript.echo "New user will be created"
Else
	createuser = False
	wscript.echo "Existing user will be used"
End If

wscript.echo ""
dim dbuser
wscript.stdOut.write "Please enter the database user to connect by (pgwatch): "
dbuser = wscript.stdin.readline
If dbuser = "" Then dbuser="pgwatch"
wscript.echo "Database user will be " & dbuser

wscript.echo ""
dim password
wscript.stdOut.write dbuser & "'s password: "
password = wscript.stdin.readline
wscript.echo "Database user will be " & dbuser & " with password " & password

wscript.echo ""
Dim tmpdir, objFSO, objSrcFile, objDstFile, line
Set tmpdir = WScript.CreateObject("Scripting.FileSystemObject").GetSpecialFolder(2)
Set objFSO = CreateObject("Scripting.FileSystemObject")
Set objSrcFile = objFSO.OpenTextFile("config/config.ini.template", 1)
Dim content

wscript.echo "Creating config.ini ..."
content = objSrcFile.ReadAll
objSrcFile.Close
content = Replace(content, "__DBUSER__", dbuser)
content = Replace(content, "__DBPASS__", dbpass)
content = Replace(content, "__PASSWORD__", password)
content = Replace(content, "__DBNAME__", dbname)
content = Replace(content, "__DBHOST__", dbhost)
content = Replace(content, "__DBPORT__", dbport)
content = Replace(content, "__HOSTNAME__", hostname)
content = Replace(content, "__FOLDER__", folder)
content = Replace(content, "__TMPDIR__", tmpdir)
Set objDstFile = objFSO.OpenTextFile("config/config.ini", 2, true)
objDstFile.WriteLine content
objDstFile.Close

wscript.echo "Preparing database creator sql script ..."
Set objSrcFile = objFSO.OpenTextFile("sql/setup.sql.template", 1)
content = objSrcFile.ReadAll
objSrcFile.Close
content = Replace(content, "__DBUSER__", dbuser)
Set objDstFile = objFSO.OpenTextFile("sql/setup.sql", 2, true)
objDstFile.WriteLine content
objDstFile.Close
wscript.echo ""

execute "Dropping database ...", """" & bins & "\dropdb.exe"" -U postgres " & dbname & " "
If createuser then
	execute "Dropping user ...", """" & bins & "\dropuser.exe"" -e -U postgres " & dbuser
	execute "Creating user ...", """" & bins & "\createuser.exe"" -e --superuser --login -U postgres --pwprompt " & dbuser
End If
execute "Creating database ...", """" & bins & "\createdb.exe"" -e -U postgres " & dbname & " "
execute "Creating content...", """" & bins & "\psql.exe"" -f sql\setup.sql -U postgres " & dbname
