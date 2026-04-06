import express from "express";
import {
  login,
  register,
  verifyToken,
  updateProfile,
  requestPackage,
  getPayment,
} from "../controllers/authController.js";
import { protect, adminOnly } from "../middleware/protect.js";
import { adminDashboardController } from "../controllers/adminController.js";

const router = express.Router();

router.post("/register", register);
router.post("/login", login);
router.get("/verify", verifyToken);
// router.get("/admin/dashboard", protect, adminOnly, adminDashboardController);
router.put("/user-package", protect, requestPackage);
router.get("/tagihan", protect, getPayment);
router.put("/update", protect, updateProfile);

export default router;
