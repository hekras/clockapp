# clockapp

### Files:
```
README.md          | this file
lockapp.jar        | distributable file, compiled with JDK11
calendar.php       | Reply to Assingment 1, PHP script that renders a calendar GUI
Clockapp.java      | Reply to Assingment 2 - server, java source code
gettimeclient.html | Reply to Assingment 2 - client, client in html/JavaScript, 
Manifest.txt       | a manifest needed to build a new clockapp.jar
```


### Distribution to servers:
cloackapp.jar is the distributable file. It is compiled with OpenJDK 11, you may need to recompile a new clockapp for the version you need. JDK Version 8.211 or higher is needed.

### Recompile and build new clockapp.jar:
```
1. start cmd.exe (windows) or termial (Ubuntu)
2. copy all files to a new folder called clockapp
3. cd <to clockapp folder>
4. javac Clockapp.java
5. jar cfm clockapp.jar Manifest.txt *.class gettimeclient.html
6. (optional) delete the *.class files
7. test the server with "java -jar clockapp.jar", if everything is ok "Server started..." is displayed
  * open browser and enter "[hostname]:8000/requesttime". result: a base64 encoded string should be returned
  * open browser and enter "[hostname]:8000/requesttimeclient". result: the client pops up
```
### Distribution:
```
   clockapp.jar contains everything needed, copy this file to a new folder on the server
```   
### Start the server:
```
   cd to the folder where clockapp.jar is copied to
   java -jar clockapp.jar
```

### Stop the server
```
goto the the cmd or terminal window and press Ctrl+C
```

## Documentation:

### REST API:
    HTTP Method | URI                                     | Action
    ------------+-----------------------------------------+-------------------------------------------------------
      GET       | http://[hostname]:8000/requesttime      | returns JSON with servertime in Base64 coded format
                |                                         | Example "fdu7fyayfadsfdfsadiuyfy98eyfd98dsf=="
    ------------+-----------------------------------------+-------------------------------------------------------
      GET       | http://[hostname]:8000/requesttimeclient| returns the gettimeclient.html 
    ------------+-----------------------------------------+-------------------------------------------------------


### Logfile on the server:
```
If any request are made to the server, they will be logged in a file in same folder
the name of the logfile is "clockserver-20190508-101112-123.log
the logfile name is gennerated each time the server starts
the logfile can be viewed in any text editor (ie. notepad nano)
each logentry is started with a timestamp in the format mm/dd/yyyy hh:MM:ss:SSS 
where mm = month, dd = day, yyyy = year, hh = hours, MM = minutes, ss = seconds and SSS = milliseconds
```

### Clockapp.java:
```
No webserver as apache, Tomcat or jetty is needed.
Default port on the httpserver is 8000, this can be changed by edting the following line in Clockapp.java:
            HttpServer server = HttpServer.create(new InetSocketAddress(8000), 0);
```

### gettimeclient.html:
```
The client only request time from the server when the button "Get server time" is pressed
the request is done with ajax
If the request is ok, the encoded and decoded values are displayed and a log entry is made on the same page
If the server is not running last ok timestamp will still be displayed and a logentry with fail will be made
All logentries can be deleted by pressing the button "Clear the log" or by reloading the page
Name of this file can be changed, but also requires to change a line in Clockapp.java:
            String sb = new String(Files.readAllBytes(Paths.get("gettimeclient.html")));
If gettimeclient.html is unavailebe to the server, the client will get "Hmmm... cannot reach this page" from the browser.
```






