import jwt from "jsonwebtoken";
import bcrypt from "bcryptjs";
import User from "../models/User.js";
import PaymentRegister from "../models/PaymentRegister.js";
import AdminMutasi from "../models/AdminMutasi.js";

const generateToken = (id) => {
  return jwt.sign({ id }, process.env.JWT_SECRET, { expiresIn: "7d" });
};

// REGISTER
export const register = async (req, res) => {
  try {
    const { email, password, selectedPackage, location } = req.body;

    if (!email || !password || !selectedPackage || !location) {
      return res.status(400).json({ message: "Missing required fields" });
    }

    const existingUser = await User.findOne({ email });
    if (existingUser)
      return res.status(400).json({ message: "User already exists" });

    const user = await User.create({
      email,
      password,
      package: selectedPackage,
      location: {
        lat: location.lat,
        lng: location.lng,
      },
    });

    const today = new Date();
    const dueDate = new Date(today.getFullYear(), today.getMonth(), 20);

    if (today > dueDate) {
      dueDate.setMonth(dueDate.getMonth() + 1);
    }

    await PaymentRegister.create({
      userId: user._id,
      trxId: null,
      amount: user.price,
      status: "pending",
      method: "cash",
      dueDate,
    });

    const token = generateToken(user._id);

    return res.status(201).json({
      success: true,
      message: "User registered successfully",
      user: {
        id: user._id,
        email: user.email,
        package: user.package,
        price: user.price,
        location: user.location,
        role: user.role,
      },
      token,
    });
  } catch (err) {
    console.error("Register Error:", err);
    return res.status(500).json({ message: "Server error" });
  }
};

// LOGIN
export const login = async (req, res) => {
  try {
    const { email, password } = req.body;

    const user = await User.findOne({ email });
    if (!user)
      return res.status(400).json({ message: "Invalid email or password" });

    const isMatch = await user.matchPassword(password);
    if (!isMatch)
      return res.status(400).json({ message: "Invalid email or password" });

    const token = generateToken(user._id);

    res.json({
      user: {
        id: user._id,
        name: user.name,
        email: user.email,
        role: user.role,
      },
      token,
    });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

// VERIFY TOKEN
export const verifyToken = async (req, res) => {
  try {
    const authHeader = req.headers.authorization;

    if (!authHeader || !authHeader.startsWith("Bearer "))
      return res.status(401).json({ message: "No token provided" });

    const token = authHeader.split(" ")[1];
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    const user = await User.findById(decoded.id).select("-password");

    if (!user) return res.status(404).json({ message: "User not found" });

    res.json({ valid: true, user });
  } catch (err) {
    res.status(401).json({ message: "Invalid token" });
  }
};

// UPDATE PROFILE
export const updateProfile = async (req, res) => {
  try {
    const userId = req.user._id;
    const { name, email, currentPassword, newPassword } = req.body;

    const user = await User.findById(userId);
    if (!user) {
      return res
        .status(404)
        .json({ success: false, message: "User not found" });
    }

    if (newPassword) {
      const isMatch = await bcrypt.compare(currentPassword, user.password);
      if (!isMatch) {
        return res
          .status(400)
          .json({ success: false, message: "Current password is incorrect" });
      }

      user.password = newPassword;
    }

    if (name) user.name = name;
    if (email) user.email = email;

    await user.save();

    return res.status(200).json({
      success: true,
      message: "Profile updated successfully",
      user: {
        id: user._id,
        name: user.name,
        email: user.email,
        role: user.role,
      },
    });
  } catch (error) {
    console.error("Profile update error:", error);
    res.status(500).json({ success: false, message: "Server error" });
  }
};
// Update Package
export const requestPackage = async (req, res) => {
  try {
    const userId = req.user._id;
    const { package: newPackage } = req.body;

    const allowedPackages = ["10MBPS", "20MBPS"];
    if (!newPackage || !allowedPackages.includes(newPackage)) {
      return res.status(400).json({
        success: false,
        message: "Invalid package selected",
      });
    }

    // Ambil data user
    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).json({
        success: false,
        message: "User not found",
      });
    }

    // Jika paket sama dengan paket aktif
    if (user.package === newPackage) {
      return res.status(400).json({
        success: false,
        message: "You already have this package",
      });
    }

    // Jika user sudah punya pending request
    if (user.pendingPackage && user.pendingPackage === newPackage) {
      return res.status(400).json({
        success: false,
        message:
          "You already requested this package. Waiting for admin approval.",
      });
    }

    // Simpan pending request
    user.pendingPackage = newPackage;
    await user.save();

    return res.status(200).json({
      success: true,
      message: "Package change request submitted. Awaiting admin approval.",
      user: {
        id: user._id,
        email: user.email,
        package: user.package, // current active package
        pendingPackage: user.pendingPackage, // new pending package
        role: user.role,
      },
    });
  } catch (error) {
    console.error("Package request error:", error);
    return res.status(500).json({
      success: false,
      message: "Server error",
    });
  }
};

// Get Payment
export const getPayment = async (req, res) => {
  try {
    const userId = req.user._id;

    // Ambil pembayaran terakhir user
    const payment = await PaymentRegister.findOne({ userId })
      .sort({ dueDate: -1 }) // ambil yang paling dekat jatuh tempo
      .lean();

    if (!payment) {
      return res.status(404).json({
        success: false,
        message: "No payment found for this user",
      });
    }

    res.status(200).json({
      success: true,
      payment,
    });
  } catch (err) {
    console.error("Get payment error:", err);
    res.status(500).json({
      success: false,
      message: "Server error",
    });
  }
};
