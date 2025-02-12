FROM debian:latest AS deepseek

WORKDIR /app

RUN apt-get update && apt-get -y install python3 pip
RUN apt-get -y install wget
RUN wget https://gist.githubusercontent.com/TheDanielPBerry/ffb6dbe950f3b2205ec80c322c6075fd/raw/97d62d3f15dcab52bff48c4fd9f7234dd61dd75b/.vimrc -O ~/.vimrc
COPY ./DeepSeek-LLM /app/DeepSeek-LLM

RUN apt-get -y install python3.11-venv
RUN python3 -m venv .

RUN apt-get -y install coreutils vim

RUN bin/pip install transformers
RUN bin/pip install torch
RUN bin/pip install torch>=2.0
RUN bin/pip install tokenizers>=0.14.0
RUN bin/pip install transformers>=4.35.0
RUN bin/pip install accelerate
RUN bin/pip install sympy==1.12
RUN bin/pip install pebble
RUN bin/pip install timeout-decorator
RUN bin/pip install attrdict



ENTRYPOINT ["sleep", "infinity"]
