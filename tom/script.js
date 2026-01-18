const express = require("express");
const mongoose = require("mongoose");

const app = express();
app.use(express.json());

mongoose.connect("mongodb://127.0.0.1:27017/crud_db")
  .then(() => console.log("MongoDB Connected"))
  .catch(err => console.log(err));

/* ===== MODEL ===== */
const UserSchema = new mongoose.Schema({
  name: String,
  email: String,
  age: Number
});

const User = mongoose.model("User", UserSchema);

/* ===== CREATE ===== */
app.post("/users", async (req, res) => {
  const user = await User.create(req.body);
  res.send(user);
});

/* ===== READ ===== */
app.get("/users", async (req, res) => {
  const users = await User.find();
  res.send(users);
});

/* ===== UPDATE ===== */
app.put("/users/:id", async (req, res) => {
  const user = await User.findByIdAndUpdate(
    req.params.id,
    req.body,
    { new: true }
  );
  res.send(user);
});

/* ===== DELETE ===== */
app.delete("/users/:id", async (req, res) => {
  await User.findByIdAndDelete(req.params.id);
  res.send({ message: "User Deleted" });
});

/* ===== SERVER ===== */
app.listen(3000, () => {
  console.log("Server running on port 3000");
});
