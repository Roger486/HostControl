variable "bucket_name" {
  description = "Nombre del bucket de Cloud Storage para backups"
  type        = string
  default     = "backups-grupo13"
}

resource "google_storage_bucket" "backups" {
  name          = var.bucket_name
  location      = "EU"
  storage_class = "STANDARD"

  versioning {
    enabled = true
  }

  lifecycle_rule {
    action {
      type = "Delete"
    }
    condition {
      age = 30
    }
  }

  uniform_bucket_level_access = true 
}

resource "google_storage_bucket_iam_binding" "cloudsql_backup_writer" {
  bucket = google_storage_bucket.backups.name
  role   = "roles/storage.objectCreator"

  members = [
    "serviceAccount:terraform-service-account@citric-nimbus-451214-d5.iam.gserviceaccount.com" 
  ]
}

resource "google_storage_bucket_iam_binding" "admin_permissions" {
  bucket = google_storage_bucket.backups.name
  role   = "roles/storage.admin"

  members = [
    "user:hugo.moreno.donoro99@gmail.com" 
  ]
}

output "bucket_url" {
  value = "gs://${google_storage_bucket.backups.name}/"
}
