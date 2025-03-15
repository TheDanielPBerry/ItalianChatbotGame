var chatInput = document.getElementById('input');
var chatHistory = document.getElementById('chat-history');
var throbber = document.getElementById('throbber');
var currentChatDescriptor = null;

const chatEndpoint = '/chat';


const ClearChatHistory = () => {
	chatHistory.innerHTML = '';
};

const ClickFeedback = (e) => {
	let message_id = e.target.getAttribute('');
	if(Number.isInteger(message_id) === false) {
		return;
	}
	document.getElementById('feedback');
	OpenModal('feedback_modal')
};

const AddHistory = (message, user, message_id) => {
	let paragraph = document.createElement('p');
	paragraph.classList.add(user);
	paragraph.classList.add('no-select');

	if(user === 'chatbot') {
		let flagButton = document.createElement('a');
		flagButton.classList.add('flag-response');
		flagButton.classList.add('material-icons');
		flagButton.innerText = 'flag';
		flagButton.title = 'Provide Feedback';
		flagButton.setAttribute('data-message-id', message_id)
		flagButton.addEventListener('click', ClickFeedback);
		paragraph.appendChild(flagButton);
	}

	if(user !== 'client') {
		let chatDescriptorSpan = document.createElement('span');
		chatDescriptorSpan.classList.add('chat-descriptor');
		chatDescriptorSpan.innerText = currentChatDescriptor;
		paragraph.appendChild(chatDescriptorSpan);
	}

	let messageSpan = document.createElement('span');
	messageSpan.innerHTML = message;
	paragraph.appendChild(messageSpan);

	chatHistory.append(paragraph);
	chatHistory.scrollHeight = '100%';
	chatHistory.scrollBy({ top: chatHistory.scrollHeight, left: 1, behavior: 'smooth'});
	setTimeout(() => chatHistory.scrollBy({ top: chatHistory.scrollHeight, left: 1, behavior: 'smooth'}), 500);
};

const SetGrammar = (message, user, message_id) => {
	let paragraph = document.createElement('p');
	paragraph.classList.add(user);
	paragraph.classList.add('no-select');

	if(user === 'chatbot') {
		let flagButton = document.createElement('a');
		flagButton.classList.add('flag-response');
		flagButton.classList.add('material-icons');
		flagButton.innerText = 'flag';
		flagButton.title = 'Provide Feedback';
		flagButton.setAttribute('data-message-id', message_id)
		flagButton.addEventListener('click', ClickFeedback);
		paragraph.appendChild(flagButton);
	}

	if(user !== 'client') {
		let chatDescriptorSpan = document.createElement('span');
		chatDescriptorSpan.classList.add('chat-descriptor');
		chatDescriptorSpan.innerText = currentChatDescriptor;
		paragraph.appendChild(chatDescriptorSpan);
	}

	let messageSpan = document.createElement('span');
	messageSpan.innerHTML = message;
	paragraph.appendChild(messageSpan);

	chatHistory.append(paragraph);
	chatHistory.scrollHeight = '100%';
	chatHistory.scrollBy({ top: chatHistory.scrollHeight, left: 1, behavior: 'smooth'});
	setTimeout(() => chatHistory.scrollBy({ top: chatHistory.scrollHeight, left: 1, behavior: 'smooth'}), 500);
};

const SendMessage = (message, descriptor) => {
	let payload = {
		'chat': descriptor,
		'message': message,
	};
	fetch('chat', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-Token': document.getElementById('chat-token').content,
		},
		body: JSON.stringify(payload),
	})
	.then(response => {
		if(!response.ok) {
			throw new Error(response.text());
		}
		return response.json()
	})
	.then(resp => {
		AddHistory(resp.grammar, 'chatbot')
		throbber.classList.add('hide');
	})
	.catch(error => console.error(error));
};

chatInput.addEventListener('keypress', (e) => {
	let input = chatInput.value.trim();
	if(e.keyCode == 13 && !e.shiftKey && input.trim() != '') {
		e.preventDefault();

		AddHistory(input, 'client');
		chatInput.value = '';

		throbber.classList.remove('hide');
		SendMessage(input, currentChatDescriptor);
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
