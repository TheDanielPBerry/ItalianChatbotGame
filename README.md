# La Vita Italiana

Proof of concept prototype for an online point and click video game that allows players to practice their conversation skills in Italian.  
The conversations within game are dyanmically generated via a Large-Language Model Backend.  
![image](/wip/gameplay.gif)



- Artwork is generated through DeepAI and edited using GIMP
- The frontend game is all done with vanilla JavaScript.
- The backend is implemented with Laravel.
- The Large Language Model is built using Rasa.  
  
Ready to play at https://danielberry.tech

## Getting Started Hosting
After cloning the repository, the 2 docker containers can be started with the following command from the project root:
```bash
docker compose up -d --build
```
This should spin up the http service on ports 80 & 443.  
The laravel configuration should be updated by copying .env.example to .env and editing the appropriate settings.  
An API auth key to the Rasa service a ChatGPT API token should be set here:
```toml
MAX_NUMBER_OF_ALLOWED_MESSAGES_PER_DAY=1000
RASA_TOKEN=************
RASA_HOST=final_project-rasa-1
RASA_PORT=5005
RASA_DOMAIN_PATH=/llm/domain.yml

CHAT_GPT_MODEL=gpt-4o-mini
CHAT_GPT_API_TOKEN=*********
```

Rasa can be started by running the following command. Make sure to substitute the token for the value saved in the laravel .env configuration.
```bash
docker exec -it final_project-rasa-1 rasa run --enable-api --auth-token=<RASA_TOKEN>
```
