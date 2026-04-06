import express from "express";
import {
  adminDashboardController,
  listUsersController,
  addUserController,
  deleteUserController,
  updateUser,
  approvePackage,
  rejectPackage,
  listPackage,
  getMutasi,
} from "../controllers/adminController.js";
import authMiddleware from "../middleware/authMiddleware.js"; // harus ada auth
import adminMiddleware from "../middleware/adminMiddleware.js"; // hanya admin

const router = express.Router();

// Users
router.get("/users", authMiddleware, adminMiddleware, listUsersController);
router.post("/users", authMiddleware, adminMiddleware, addUserController);
router.delete(
  "/users/:id",
  authMiddleware,
  adminMiddleware,
  deleteUserController
);
router.put("/users/:id", authMiddleware, adminMiddleware, updateUser);
router.put(
  "/package-requests/:userId/approve",
  authMiddleware,
  adminMiddleware,
  approvePackage
);
router.put(
  "/package-requests/:userId/reject/",
  authMiddleware,
  adminMiddleware,
  rejectPackage
);
router.get("/package-requests", authMiddleware, adminMiddleware, listPackage);
router.get("/mutasi", authMiddleware, adminMiddleware, getMutasi);
router.get(
  "/dashboard",
  authMiddleware,
  adminMiddleware,
  adminDashboardController
);
export default router;
