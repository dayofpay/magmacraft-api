import requests

url = "https://v-devs.online/api.php?projectName=magmacraft&functionName=getTopTokens"

response = requests.get(url)

data = response.json()
