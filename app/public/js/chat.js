var chatInput = document.getElementById('input');
var chatHistory = document.getElementById('chat-history');
var throbber = document.getElementById('throbber');
var feedbackForm = document.getElementById('feedback-form');
var currentChatDescriptor = null;

const chatEndpoint = '/chat';


const ClearChatHistory = () => {
	chatHistory.innerHTML = '';
};

const ClickFeedback = (e) => {
	let message_id = e.target.getAttribute('data-message-id');
	if(Number.isNaN(parseInt(message_id))) {
		return;
	}
	document.getElementById('feedback_message_id').value = message_id;
	OpenModal('feedback_modal');
};

const SubmitFeedback = (e) => {
	e.preventDefault();
	let feedback_payload = Object.fromEntries(feedbackForm.querySelectorAll('input,select,textarea').entries().map(([_, element]) => [element.name, element.value]));
	let feedback_type = document.getElementById('feedback_type');
	if(feedback_payload['feedback_type'] == -1) {
		feedback_type.classList.add('error');
		return;
	} else {
		feedback_type.classList.remove('error');
		feedback_type.value = -1;
	}
	CloseModal('feedback-modal');
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
		AddHistory(resp.grammar, 'chatbot', resp.message_id)
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
feedbackForm.addEventListener('submit', SubmitFeedback)


/**
 * @param chatDescriptor string
 */
const LoadChat = (chatDescriptor) => {
	ClearChatHistory();
	chatInput.focus();
	currentChatDescriptor = chatDescriptor;
	feedbackForm = document.getElementById('feedback-form');
};
