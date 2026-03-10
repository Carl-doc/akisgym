document.addEventListener("click", function(e){

if(e.target.classList.contains("delete-btn")){

if(confirm("Delete this member?")){
e.target.closest("tr").remove();
}

}

if(e.target.classList.contains("edit-btn")){

const row = e.target.closest("tr");

const id = row.children[0].textContent;
const name = row.children[1].textContent;
const contact = row.children[2].textContent;
const email = row.children[3].textContent;

document.getElementById("memberId").value = id;
document.getElementById("memberName").value = name;
document.getElementById("memberContact").value = contact;
document.getElementById("memberEmail").value = email;

memberModal.classList.add("show");

}

});