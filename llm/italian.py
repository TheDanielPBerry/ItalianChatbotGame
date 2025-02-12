from transformers import AutoModelForCausalLM, AutoTokenizer
device = "cpu" # the device to load the model onto

model = AutoModelForCausalLM.from_pretrained(
    "yzhuang/Qwen1.5-7B-Chat_fictional_arc_challenge_Italian_v1",
    torch_dtype="auto",
    device_map="auto"
)
tokenizer = AutoTokenizer.from_pretrained("yzhuang/Qwen1.5-7B-Chat_fictional_arc_challenge_Italian_v1")

prompt = "Ciao. Posso avere prosciutto."
messages = [
    {"role": "system", "content": "Sei un macellaio che lavora in un supermercato. Si possono trovare due tipi di prosciutto: cotto e crudo. Il prezzo del prosciutto cotto è 4,75."},
    {"role": "user", "content": prompt}
]
text = tokenizer.apply_chat_template(
    messages,
    tokenize=False,
    add_generation_prompt=True
)
model_inputs = tokenizer([text], return_tensors="pt").to(device)

generated_ids = model.generate(
    model_inputs.input_ids,
    max_new_tokens=512
)
generated_ids = [
    output_ids[len(input_ids):] for input_ids, output_ids in zip(model_inputs.input_ids, generated_ids)
]

response = tokenizer.batch_decode(generated_ids, skip_special_tokens=True)[0]

print(response)
