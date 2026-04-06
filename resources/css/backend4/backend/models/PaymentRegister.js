import mongoose from "mongoose";

const paymentRegisterSchema = new mongoose.Schema(
  {
    trxId: {
      type: String,
      required: false,
      default: null,
    },

    userId: {
      type: mongoose.Schema.Types.ObjectId,
      ref: "User",
      required: true,
    },

    amount: {
      type: Number,
      required: true,
    },

    status: {
      type: String,
      enum: ["pending", "success", "failed"],
      default: "pending",
    },

    method: {
      type: String,
      enum: ["cash", "transfer", "online"],
      default: "transfer",
    },

    dueDate: {
      type: Date,
      required: true,
    },

    paidAt: {
      type: Date,
      default: null,
    },

    isActive: {
      type: Boolean,
      default: false,
      // true = user sudah diperpanjang
      // false = masih menunggu pembayaran / expired
    },
  },
  { timestamps: true }
);

const PaymentRegister = mongoose.model(
  "PaymentRegister",
  paymentRegisterSchema
);
export default PaymentRegister;
