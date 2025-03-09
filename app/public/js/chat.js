var chatInput = document.getElementById('input');
var chatHistory = document.getElementById('chat-history');
var currentChatDescriptor = null;

const ClearChatHistory = () => {
	chatHistory.innerHTML = '';
};

const AddHistory = (message, user) => {
	let paragraph = document.createElement('p');
	paragraph.classList.add(user);
	paragraph.classList.add('no-select');
	if(user !== 'client') {
		message = `<span class="chat-descriptor">${currentChatDescriptor}:</span>` + message;
	}
	paragraph.innerHTML = message;
	chatHistory.append(paragraph);
	chatHistory.scrollHeight = '100%';
	chatHistory.scrollBy({ top: chatHistory.scrollHeight, left: 1, behavior: 'smooth'});
	setTimeout(() => chatHistory.scrollBy({ top: chatHistory.scrollHeight, left: 1, behavior: 'smooth'}), 500);
};

chatInput.addEventListener('keypress', (e) => {
	let input = chatInput.value.trim();
	if(e.keyCode == 13 && !e.shiftKey && input != '') {
		AddHistory(input, 'client');
		chatInput.value = '';
		e.preventDefault();
	}
});


/**
 * @param chatDescriptor string
 */
const LoadChat = (chatDescriptor) => {
	ClearChatHistory();
	chatInput.focus();
	currentChatDescriptor = chatDescriptor;
};
