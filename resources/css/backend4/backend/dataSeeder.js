import mongoose from "mongoose";
import dotenv from "dotenv";
import User from "./models/User.js"; // sesuaikan path model kamu
import bcrypt from "bcryptjs";

dotenv.config();

// Tambah data user
const users = [
  {
    name: "Admin Default",
    email: "admin@admin.com",
    password: "admin123",
    role: "admin",
  },
  //optional data ,{
  // name: "Member Satu",
  // email: "member@example.com",
  // password: "member123",
  // role: "member",
  //},
];

const seedUsers = async () => {
  try {
    await mongoose.connect(process.env.MONGO_URI);
    console.log("MongoDB Connected");

    const hashedUsers = await Promise.all(
      users.map(async (u) => ({
        ...u,
        password: await bcrypt.hash(u.password, 10),
      }))
    );

    await User.insertMany(hashedUsers);
    console.log("User seeder SUCCESS");
    process.exit();
  } catch (err) {
    console.error(err);
    process.exit(1);
  }
};

seedUsers();
