let survey = document.getElementById('survey-form');

const SubmitSurvey = (e) => {
	e.preventDefault();
	let survey_payload = ParseForm(survey);
	//survey.reset();

	fetch('survey', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-Token': document.getElementById('chat-token').content,
		},
		body: JSON.stringify(survey_payload),
	})
	.then(response => response.json())
	.then(resp => {
		if(resp.success === 1) {
			SpawnNotification('Survey Successfully Submitted', 2000);
			CloseModal('survey_modal');
			document.getElementById('surveyed').value = 1;
		} else {
			if(Array.isArray(resp.errors)) {
				CloseModal('survey_modal');
				SpawnNotification(resp.errors[0], 2000);
				return;
			}
			Object.entries(resp.errors).forEach(([name]) => {
				survey.querySelector(`[name=${name}]`).classList.add('error');
			});
		}
	})
	.catch(error => console.error(error));
};

survey.addEventListener('submit', SubmitSurvey);
