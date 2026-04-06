import User from "../models/User.js";
import AdminMutasi from "../models/AdminMutasi.js";
import bcrypt from "bcryptjs";

export const adminDashboardController = async (req, res) => {
  try {
    const members = await User.find({ role: "member" }).lean();
    const totalUsers = members.length;

    const totalRevenue = members.reduce((sum, u) => sum + (u.price || 0), 0);

    const expiredUsers = members.filter((u) => !u.payment?.isActive).length;

    const lastPayments = await Promise.all(
      members.map(async (u) => {
        const lastPayment = await AdminMutasi.findOne({ userId: u._id })
          .sort({ createdAt: -1 })
          .lean();

        return {
          id: u._id,
          name: u.name,
          email: u.email,
          package: u.package,
          amount: lastPayment?.amount || 0,
          status: lastPayment?.status || "pending",
          paidAt: lastPayment?.paidAt || null,
        };
      })
    );

    const latestMutations = await AdminMutasi.find()
      .sort({ createdAt: -1 })
      .limit(10)
      .populate("userId", "name email package")
      .lean();

    res.status(200).json({
      success: true,
      data: {
        totalUsers,
        totalRevenue,
        expiredUsers,
        members: lastPayments,
        latestMutations,
      },
    });
  } catch (err) {
    console.error("Admin dashboard error:", err);
    res.status(500).json({ success: false, message: "Server error" });
  }
};

export const listUsersController = async (req, res) => {
  try {
    const users = await User.find().select("-password"); // jangan kirim password
    res.status(200).json({ success: true, users });
  } catch (err) {
    console.error(err);
    res.status(500).json({ success: false, message: "Server error" });
  }
};

export const addUserController = async (req, res) => {
  try {
    const { name, email, password, role } = req.body;

    const existing = await User.findOne({ email });
    if (existing)
      return res
        .status(400)
        .json({ success: false, message: "Email already exists" });

    const salt = await bcrypt.genSalt(10);
    const hashedPassword = await bcrypt.hash(password, salt);

    const user = await User.create({
      name,
      email,
      password: hashedPassword,
      role,
    });
    res.status(201).json({ success: true, user });
  } catch (err) {
    console.error(err);
    res.status(500).json({ success: false, message: "Server error" });
  }
};

export const deleteUserController = async (req, res) => {
  try {
    const { id } = req.params;
    await User.findByIdAndDelete(id);
    res.status(200).json({ success: true, message: "User deleted" });
  } catch (err) {
    console.error(err);
    res.status(500).json({ success: false, message: "Server error" });
  }
};

export const getAllUsers = async (req, res) => {
  try {
    const users = await User.find().select("-password"); // jangan kirim password
    res.status(200).json({ success: true, users });
  } catch (err) {
    console.error("Get users error:", err);
    res.status(500).json({ success: false, message: "Server error" });
  }
};

export const updateUser = async (req, res) => {
  try {
    const { name, role, package: pkg, location } = req.body;

    const priceMap = {
      "10MBPS": 150000,
      "20MBPS": 250000,
    };

    const updated = await User.findByIdAndUpdate(
      req.params.id,
      {
        name,
        role,
        package: pkg,
        price: priceMap[pkg] || 0,
        location: {
          lat: location?.lat ?? null,
          lng: location?.lng ?? null,
        },
      },
      { new: true }
    );

    res.json({ success: true, user: updated });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
};

export const approvePackage = async (req, res) => {
  try {
    const { userId } = req.params;

    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).json({
        success: false,
        message: "User not found",
      });
    }

    if (!user.pendingPackage) {
      return res.status(400).json({
        success: false,
        message: "No pending package request for this user",
      });
    }

    const priceMap = {
      "10MBPS": 150000,
      "20MBPS": 250000,
    };

    user.package = user.pendingPackage;
    user.price = priceMap[user.pendingPackage] || 0;
    user.pendingPackage = null;

    await user.save();

    return res.status(200).json({
      success: true,
      message: "Package change approved",
      user: {
        id: user._id,
        email: user.email,
        package: user.package,
        price: user.price,
        role: user.role,
      },
    });
  } catch (error) {
    console.error("Approve error:", error);
    res.status(500).json({ success: false, message: "Server error" });
  }
};

export const rejectPackage = async (req, res) => {
  try {
    const { userId } = req.params;

    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).json({
        success: false,
        message: "User not found",
      });
    }

    if (!user.pendingPackage) {
      return res.status(400).json({
        success: false,
        message: "No pending package request for this user",
      });
    }

    // Hanya hapus pending request
    const requested = user.pendingPackage;
    user.pendingPackage = null;

    await user.save();

    return res.status(200).json({
      success: true,
      message: `Package change request (${requested}) has been rejected`,
      user: {
        id: user._id,
        email: user.email,
        package: user.package, // paket tetap
        pendingPackage: null,
        role: user.role,
      },
    });
  } catch (error) {
    console.error("Reject error:", error);
    res.status(500).json({ success: false, message: "Server error" });
  }
};

export const listPackage = async (req, res) => {
  try {
    const requests = await User.find({
      pendingPackage: { $ne: null },
    }).select("name email package pendingPackage");

    res.json({ success: true, requests });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
};
export const getMutasi = async (req, res) => {
  try {
    // Jika ingin filter per user, bisa pakai query: /admin/mutasi?userId=xxx
    const { userId } = req.query;

    let query = {};
    if (userId) query.userId = userId;

    const mutasi = await AdminMutasi.find(query)
      .populate("userId", "name email package") // menampilkan info user
      .sort({ createdAt: -1 }); // terbaru di atas

    res.status(200).json({
      success: true,
      count: mutasi.length,
      data: mutasi,
    });
  } catch (err) {
    console.error("Error fetching admin mutasi:", err);
    res.status(500).json({
      success: false,
      message: "Server error",
    });
  }
};
