using System;
using System.Net;
using Newtonsoft.Json;

namespace ConsoleApp
{
    class Program
    {
        static void Main(string[] args)
        {
            // Задайте адреса
            string url = "https://v-devs.online/api.php?projectName=magmacraft&functionName=getClanPlayers&clanName=Gapple";
            WebClient client = new WebClient();
            string response = client.DownloadString(url);

            // Анализирайте JSON датата използвайки Newtonsoft.Json библиотеката
            JsonObject data = JsonConvert.DeserializeObject<JsonObject>(response);

            // Вашето действие
        }
    }
}
