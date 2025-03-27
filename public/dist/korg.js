window.onload = function() {
  document.getElementById("send-to-korg").onsubmit = function(event) {
    event.preventDefault();

    const userInput = document.getElementById("input-prompt").value.trim();
    if (userInput === "") return; // Évite d'envoyer une requête vide

    displayMessage(userInput);  // Afficher la question de l'utilisateur
    callIA(userInput);          // Envoyer la question à l'IA
    document.getElementById("input-prompt").value = ""; // Vider le champ après envoi
  };
};

async function callIA(prompt) {
  console.log("Question envoyée :", prompt);

  try {
    const response = await fetch("http://127.0.0.1:1234/v1/chat/completions", {
      method: "POST",
      mode: "cors",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        "model": "mistral-7b-grok", // nom du modèle
        "messages": [
          { 
            "role": "system", 
            "content": "Tu es une intelligence artificielle avancée, conçue pour répondre avec précision et clarté à toutes les questions en francais." 
          },
          { 
            "role": "user", 
            "content": prompt
          }
        ],
      }),
    });

    if (!response.ok) {
      throw new Error(`Erreur ! Statut : ${response.status}`);
    }

    const data = await response.json();
    const content = data.choices[0].message.content;
    console.log("Réponse de l'IA :", content);

    displayResponse(content);
  } catch (error) {
    displayResponse("Erreur : " + error.message);
  }
}

function displayResponse(message) {
  const container = document.getElementById("container-korg");
  container.classList.add("p-6");

  const responseText = document.createElement("div");
  responseText.classList.add("w-4/5", "h-fit", "bg-neutral-700", "rounded-3xl", "p-3", "pr-6", "rounded-tl-none", "ml-6", "flex", "gap-3");

  const imageText = document.createElement("img");
  imageText.src = "/assets/Icons/korg.png";
  imageText.classList.add("w-8", "h-8");

  const contentText = document.createElement("p");
  contentText.classList.add("text-md");
  contentText.innerText = message || "No response";

  responseText.appendChild(imageText);
  responseText.appendChild(contentText);
  container.appendChild(responseText);
}

function displayMessage(message) {
  const container = document.getElementById("container-korg");
  container.classList.add("p-6");

  const responseText = document.createElement("div");
  responseText.classList.add("w-4/5", "h-fit", "bg-neutral-700", "rounded-3xl", "py-3", "px-6", "rounded-tr-none", "ml-32", "flex", "gap-3");

  const contentText = document.createElement("p");
  contentText.classList.add("text-md");
  contentText.innerText = message;

  responseText.appendChild(contentText);
  container.appendChild(responseText);
}