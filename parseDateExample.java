import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonReader;

public class Main {
  public static void main(String[] args) throws IOException {
    URL url = new URL("https://v-devs.online/api.php?projectName=magmacraft&functionName=getClanPlayers&clanName=Gapple");
    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
    connection.setRequestMethod("GET");
    InputStream inputStream = connection.getInputStream();
    JsonReader reader = Json.createReader(inputStream);
    JsonObject data = reader.readObject();
  }
}
