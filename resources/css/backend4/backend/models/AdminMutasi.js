import mongoose from "mongoose";

const adminMutasiSchema = new mongoose.Schema(
  {
    trxId: {
      type: String,
      required: true,
      unique: true,
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
      default: "cash",
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
    },
  },
  { timestamps: true }
);

const AdminMutasi = mongoose.model("AdminMutasi", adminMutasiSchema);
export default AdminMutasi;
