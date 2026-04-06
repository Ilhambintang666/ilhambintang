import mongoose from "mongoose";
import bcrypt from "bcryptjs";

const userSchema = new mongoose.Schema(
  {
    name: {
      type: String,
      trim: true,
      default: "Brad Pitt",
    },

    email: {
      type: String,
      required: true,
      unique: true,
      lowercase: true,
      trim: true,
    },

    password: {
      type: String,
      required: true,
      minlength: 6,
    },

    role: {
      type: String,
      enum: ["member", "admin"],
      default: "member",
    },

    // 🟦 Paket Internet
    package: {
      type: String,
      enum: ["10MBPS", "20MBPS"],
      required: true,
    },

    // 🟦 Harga otomatis sesuai paket
    price: {
      type: Number,
      required: true,
      default: 0,
    },

    location: {
      lat: { type: Number, default: null },
      lng: { type: Number, default: null },
    },
  },
  { timestamps: true }
);

// 🔧 Set harga otomatis sesuai paket sebelum save()
userSchema.pre("save", function (next) {
  if (this.package === "10MBPS") {
    this.price = 210000;
  } else if (this.package === "20MBPS") {
    this.price = 210000;
  }
  next();
});

// 🔒 Hash password
userSchema.pre("save", async function (next) {
  if (!this.isModified("password")) return next();
  const salt = await bcrypt.genSalt(10);
  this.password = await bcrypt.hash(this.password, salt);
  next();
});

// Compare password
userSchema.methods.matchPassword = async function (enteredPassword) {
  return await bcrypt.compare(enteredPassword, this.password);
};

const User = mongoose.model("User", userSchema);
export default User;
