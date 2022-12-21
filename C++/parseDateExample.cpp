#include <iostream>
#include <string>
#include <cpr/cpr.h>

int main() {
  // Задайте URL адреса и изпратете заявката
  auto response = cpr::Get(cpr::Url{"https://v-devs.online/api.php?projectName=magmacraft&functionName=getClanPlayers&clanName=Gapple"});

  // Проверете статуса, за да сте сигурни, че заявката е изпратена успешно
  if (response.status_code == 200) {
    // Aнализирайте JSON датата използвайки jsoncpp библиотеката
    Json::Value root;
    Json::CharReaderBuilder builder;
    std::string errors;
    std::istringstream iss(response.text);
    bool parsedSuccess = Json::parseFromStream(builder, iss, &root, &errors);

    if (parsedSuccess) {
      // Вашето действие
    } else {
      std::cerr << "Грешка при опит да бъде Parse-ната JSON датата: " << errors << std::endl;
    }
  } else {
    std::cerr << "Заявката върна грешка " << response.status_code << std::endl;
  }

  return 0;
}
