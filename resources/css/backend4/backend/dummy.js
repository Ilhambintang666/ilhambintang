import mongoose from "mongoose";
import AdminMutasi from "./models/AdminMutasi.js"; // sesuaikan path
import dotenv from "dotenv";
import { generateTrxId } from "./utils/trxId.js";

dotenv.config();

const DB_URI = process.env.MONGO_URI || "mongodb://127.0.0.1:27017/yourdbname";

mongoose
  .connect(DB_URI, {
    useNewUrlParser: true,
    useUnifiedTopology: true,
  })
  .then(() => console.log("MongoDB connected"))
  .catch((err) => console.error("MongoDB connection error:", err));

const seedPayments = async () => {
  try {
    const userId = "69285f7af0da69c6665303bc";
    const trx = generateTrxId();
    const payments = [
      {
        trxId: trx,
        userId,
        amount: 210000,
        status: "pending",
        method: "transfer",
        dueDate: new Date("2025-12-20"),
        isActive: false,
      },
      {
        trxId: "TRX1002",
        userId,
        amount: 210000,
        status: "success",
        method: "transfer",
        dueDate: new Date("2025-11-20"),
        paidAt: new Date("2025-11-20T10:00:00"),
        isActive: true,
      },
    ];

    await AdminMutasi.insertMany(payments);

    console.log("Dummy payments inserted successfully!");
    process.exit();
  } catch (err) {
    console.error("Error inserting dummy payments:", err);
    process.exit(1);
  }
};

seedPayments();
