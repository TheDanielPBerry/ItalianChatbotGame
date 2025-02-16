var chatInput = document.getElementById('input');
var chatHistory = document.getElementById('chat-history');

const AddHistory = (message, user) => {
	let paragraph = document.createElement('p');
	paragraph.classList.add(user);
	paragraph.innerText = message;
	chatHistory.append(paragraph);
	chatHistory.scrollHeight = '100%';
	chatHistory.scrollBy({ top: chatHistory.scrollHeight, left: 1, behavior: 'smooth'});
};
chatInput.addEventListener('keypress', (e) => {
	if(e.keyCode == 13 && !e.shiftKey) {
		AddHistory(chatInput.value, 'client');
		chatInput.value = '';
		e.preventDefault();
	}
});
