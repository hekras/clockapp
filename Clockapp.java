
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpServer;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.io.OutputStream;
import java.net.InetSocketAddress;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Base64;
import java.util.Date;
import java.util.Iterator;
import java.util.Map;

/**
 *
 * @author Henryk Krasuski
 */
public class Clockapp implements Runnable {

    public Clockapp() {

        // init logger
        DateFormat dateFormat = new SimpleDateFormat("yyyyMMdd-kkmmss-SSS");
        ClockServerHandler.logFileName = "clockserver-" + dateFormat.format(new Date()) + ".log";

        // init HTTP server
        try {
            HttpServer server = HttpServer.create(new InetSocketAddress(8000), 0);
            server.createContext("/requesttimeclient", new ClockClientHandler());
            server.createContext("/requesttime", new ClockServerHandler());
            server.setExecutor(null); // creates a default executor
            System.out.println("Server started...");
            server.start();
        } catch (IOException ex) {
            System.out.println(ex.getMessage());
            ex.printStackTrace();
        }
    }

    public static void main(String[] args) {

        Thread t = new Thread(new Clockapp());
        t.start();
    }

    /** loop that creates the timestamp every 60000 ms
     * 
     */
    @Override
    public void run() {
        while (true) {
            try {
                ClockServerHandler.currentTime = ClockServerHandler.responseDateFormat.format(new Date());
                Thread.sleep(60000);
            } catch (InterruptedException ex) {
            }
        }
    }

    /** handles time requests and logging
     * 
     */
    static class ClockServerHandler implements HttpHandler {

        final static DateFormat responseDateFormat = new SimpleDateFormat("dd/MM/yyyy kk:mm");
        final static DateFormat logDateFormat = new SimpleDateFormat("dd/MM/yyyy kk:mm:ss:SSS");
        static String currentTime;
        static String logFileName;

        @Override
        public void handle(HttpExchange t) throws IOException {
            String response = "{\"value\":\"" + currentTime + "\"}";
            byte[] encodedResponse = Base64.getEncoder().encode(response.getBytes());
            t.sendResponseHeaders(200, encodedResponse.length);
            OutputStream os = t.getResponseBody();
            os.write(encodedResponse);
            os.close();

            // log the request
            BufferedWriter out = new BufferedWriter(new FileWriter(logFileName, true));
            out.write(logDateFormat.format(new Date())+", request from:\n");
            out.write("    Host ip: " + t.getRemoteAddress().toString()+"\n");
            out.write("    Request Method: " + t.getRequestMethod()+"\n");
            Iterator it = t.getRequestHeaders().entrySet().iterator();
            // log all headers
            while (it.hasNext()) {
                Map.Entry pair = (Map.Entry) it.next();
                out.write("    " + pair.getKey() + " = " + pair.getValue()+"\n");
            }
            out.write("\n");
            out.close();

        }
    }

    /** handles the request for the client
     * 
     */
    static class ClockClientHandler implements HttpHandler {

        @Override
        public void handle(HttpExchange t) throws IOException {
//            String sb = new String(Files.readAllBytes(Paths.get("gettimeclient.html")));
            byte[] sb = Clockapp.class.getResourceAsStream("gettimeclient.html").readAllBytes();
            t.sendResponseHeaders(200, sb.length);
            OutputStream os = t.getResponseBody();
            os.write(sb);
            os.close();
        }
    }
}
