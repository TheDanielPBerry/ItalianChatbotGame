var chatInput = document.getElementById('input');
var chatHistory = document.getElementById('chat-history');
var throbber = document.getElementById('throbber');
var feedbackForm = document.getElementById('feedback-form');
var currentChatDescriptor = null;

const chatEndpoint = '/chat';

const ParseForm = (form) => Object.fromEntries(form.querySelectorAll('input,select,textarea').entries().map(([_, element]) => [element.name, element.value]));

const ClearChatHistory = () => {
	chatHistory.querySelectorAll('p').forEach((el) => el.remove());
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
	let feedback_type = document.getElementById('feedback_type');
	let feedback_payload = ParseForm(feedbackForm);
	if(feedback_payload['feedback_type'] == -1) {
		feedback_type.classList.add('error');
		return;
	} else {
		feedback_type.classList.remove('error');
	}
	feedbackForm.reset();

	fetch('feedback', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-Token': document.getElementById('chat-token').content,
		},
		body: JSON.stringify(feedback_payload),
	})
	.then(response => response.json())
	.then(resp => {
		if(resp.success === 1) {
			SpawnNotification('Feedback Successfully Submitted', 2000);
		} else {
			SpawnNotification(resp.errors[0], 2000);
		}
	})
	.catch(error => console.error(error))
	.finally(() => CloseModal('feedback_modal'));
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

	chatHistory.insertBefore(paragraph, throbber);
	chatHistory.scrollHeight = '100%';
	chatHistory.scrollBy({ top: chatHistory.scrollHeight, left: 1, behavior: 'smooth'});
	setTimeout(() => chatHistory.scrollBy({ top: chatHistory.scrollHeight, left: 1, behavior: 'smooth'}), 500);
};

const SetGrammar = (grammarAnalysis) => {
	if(grammarAnalysis.toLowerCase() === 'yes') {
		grammarAnalysis = '<b><span class="material-icons" style="position: relative; top: 5px; left: -1px;">verified</span>Corretto</b>';
	} else if(grammarAnalysis.toLowerCase() === 'english') {
		grammarAnalysis = '<b>Inglese?</b>';
	}
	document.getElementById('grammar').innerHTML = grammarAnalysis;
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
	.then(response => response.json())
	.then(resp => {
		if(resp.errors) {
			let tmp = currentChatDescriptor;
			currentChatDescriptor = 'La Vita Italiana';
			AddHistory(resp.errors[0], 'narrator', resp.message_id);
			currentChatDescriptor = tmp;
		} else {
			AddHistory(resp.prediction, 'chatbot', resp.message_id);
			SetGrammar(resp.grammar);
		}
	})
	.catch(error => console.error(error))
	.finally(() => throbber.classList.add('hide'));
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
