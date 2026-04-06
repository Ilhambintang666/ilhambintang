// middleware/adminAuth.js
import jwt from "jsonwebtoken";
import User from "../models/User.js";

const adminMiddleware = async (req, res, next) => {
  try {
    const authHeader = req.headers.authorization;

    if (!authHeader || !authHeader.startsWith("Bearer ")) {
      return res
        .status(403)
        .json({ success: false, message: "No token provided" });
    }

    const token = authHeader.split(" ")[1];

    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    const user = await User.findById(decoded.id);

    if (!user)
      return res
        .status(401)
        .json({ success: false, message: "User not found" });
    if (user.role !== "admin")
      return res
        .status(403)
        .json({ success: false, message: "Not authorized" });

    req.user = user;
    next();
  } catch (err) {
    console.error("Admin Auth Error:", err);
    res.status(403).json({ success: false, message: "Invalid token" });
  }
};
export default adminMiddleware;
