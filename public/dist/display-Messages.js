async function callPHP() {
  try {
    const response = await fetch("http://localhost:5501/message");
    
    if (!response.ok) {
      throw new Error(`ERROR! Status: ${response.status}`);
    }
    
    const data = await response.json();
    console.log(data);
    // const content = data.choices[0].message.content;
    // console.log(content);
    
    // displayResponse(content);
  } catch (error) {
    displayResponse("Error: " + error.message);
  }
}


function displayMessage(message) {
  const container = document.getElementById("container-korg");
  container.classList.add("p-6")
  const responseText = document.createElement("div");
  responseText.classList.add("w-4/5", "h-fit", "bg-neutral-700", "rounded-3xl", "p-3", "pr-6", "rounded-tl-none", "ml-6", "flex", "gap-3");

  const imageText = document.createElement("img");
  imageText.src = "/assets/Icons/korg.png";
  imageText.classList.add("w-8", "h-8")


  const contentText = document.createElement("p");
  contentText.classList.add("text-md")
  contentText.innerText = message || "No response";
  
  responseText.appendChild(imageText);
  responseText.appendChild(contentText);
  container.appendChild(responseText);
}

window.onload = async function() {
  // console.log("hello");
  try {
    const response = await fetch("http://localhost:5501/message", {
      method: "POST",
      headers: {
          "Content-Type": "application/json"
      }
    });
    
    if (!response.ok) {
      throw new Error(`ERROR! Status: ${response.status}`);
    }
    
    console.log(response);
    // const data = await response.json();
    var db = JSON.stringify(response);
    var db = JSON.parse(db);
    console.log(db);
    // const content = data.choices[0].message.content;
    // console.log(content);
    
    // displayResponse(content);
  } catch (error) {
    console.log("Error: " + error.message);
  }
}
