terraform {
  required_providers {
    google = {
      source  = "hashicorp/google"
      version = "~> 4.0"
    }
  }
}

provider "google" {
  credentials = file(var.credentials_file)
  project     = var.project_id
  region      = "europe-west1"
}

variable "credentials_file" {
  description = "Ruta del archivo JSON de credenciales"
  type        = string
}

variable "project_id" {
  description = "ID del proyecto en Google Cloud"
  type        = string
}

output "terraform_ready" {
  value = "Terraform está listo para Google Cloud!"
}

