@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4>EstateFlow AI Assistant</h4>

        </div>

        <div class="card-body">

            <div id="chat-box"
                 style="height:450px;
                        overflow-y:auto;
                        border:1px solid #ddd;
                        padding:15px;
                        margin-bottom:15px;">

            </div>

            <div class="input-group">

                <input
                    type="text"
                    id="message"
                    class="form-control"
                    placeholder="Ask anything...">

                <button
                    class="btn btn-primary"
                    id="send">

                    Send

                </button>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

const chatBox = document.getElementById('chat-box');

document.getElementById('send').addEventListener('click', function () {

    const messageInput = document.getElementById('message');
    const message = messageInput.value.trim();

    if (!message) return;

    chatBox.innerHTML += `
        <div class="text-end mb-2">
            <b>You:</b><br>
            ${message}
        </div>
    `;

    messageInput.value = "";

    fetch("{{ route('assistant.chat') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {

        console.log(data);

      chatBox.innerHTML += `
    <div class="text-start mb-3">
        <b>AI Debug:</b>
        <pre>${JSON.stringify(data, null, 2)}</pre>
    </div>
`;

        chatBox.scrollTop = chatBox.scrollHeight;

    })
    .catch(error => {

        console.error(error);

        chatBox.innerHTML += `
            <div class="text-danger">
                <b>Error:</b> ${error}
            </div>
        `;

    });

});

</script>


@endsection