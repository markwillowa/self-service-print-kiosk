# Piso Print

Offline-first self-service printing kiosk system built with Laravel for Raspberry Pi touchscreen kiosk environments.

---

# Project Overview

Piso Print is a kiosk-based printing system designed for:

- Raspberry Pi 4
- Touchscreen kiosk environments
- Epson printers
- Bluetooth file transfer
- Coin-operated printing
- Unattended public usage

The system focuses heavily on:

- Kiosk security
- Offline reliability
- Print queue safety
- Tamper resistance
- Public terminal hardening

---

# Technology Stack

## Backend

- Laravel 13
- PHP 8.4
- SQLite

## Frontend

- Blade
- Tailwind CSS
- Vite

## Printing

- CUPS (planned)
- PDF-based print flow
- qpdf
- pdfinfo
- LibreOffice headless conversion

---

# Planned Hardware

- Raspberry Pi 4
- LAFVIN 7-inch touchscreen (1024x600)
- Epson L121
- Bluetooth file transfer
- Coin acceptor

---

# Core Application Flow

```text
Upload file
→ Validate file
→ Convert to PDF (if needed)
→ Count pages
→ Preview document
→ Select pages
→ Choose print mode
→ Payment
→ Queue print job
→ Print
→ Cleanup
```

---

# Completed Features

## 1. Kiosk UI System

### Implemented

- Full-screen kiosk layout
- 1024x600 optimized UI
- Touch-friendly controls
- Fullscreen PDF preview
- Modern kiosk styling
- Responsive kiosk structure

### Files

```text
resources/views/components/kiosk-layout.blade.php
resources/views/kiosk/*
```

---

## 2. File Upload System

### Supported Formats

- PDF
- DOC
- DOCX
- XLS
- XLSX
- PPT
- PPTX
- JPG
- JPEG
- PNG
- TXT

### Features

- Secure upload handling
- Storage organization
- Upload validation

---

## 3. File Conversion System

### Implemented

- LibreOffice headless conversion
- PDF normalization pipeline
- Automatic PDF conversion

### Architecture

```text
Original File
→ Convert to PDF
→ Preview PDF
→ Print PDF
```

### Files

```text
app/Services/FileConverter.php
```

---

## 4. PDF Preview System

### Implemented

- Inline PDF preview
- Fullscreen preview
- Signed preview URLs
- Temporary preview access
- Page-filtered preview support

### Security

- Signed temporary URLs
- Expiration-based access

---

## 5. Page Selection

### Implemented

- Custom page ranges
- Page filtering
- qpdf extraction
- Filtered PDF generation

### Supported Input

```text
all
1-3
2,4,8
1-3,5,8
```

### Files

```text
app/Services/PageSelectionParser.php
app/Services/PdfPageExtractor.php
```

---

## 6. Print Mode Selection

### Implemented

- Black-only printing
- Colored printing
- Dynamic pricing

### Pricing

| Mode | Price |
|------|------|
| Black | ₱1/page |
| Color | ₱2/page |

---

## 7. Payment System

### Implemented

- Dummy credits
- Simulated coin insertion
- Dynamic pricing updates
- Payment completion state

### Future

- Real coin acceptor
- GCash
- QR payments

---

## 8. Queue System

### Implemented

- Queued jobs
- Printing jobs
- Completed jobs
- Failed jobs
- Queue processing command

### Queue Flow

```text
pending_payment
→ paid
→ queued
→ printing
→ completed
```

### Files

```text
app/Console/Commands/ProcessPrintQueue.php
app/Services/PrinterService.php
app/Services/PrintJobStateService.php
```

---

## 9. Session Expiration

### Implemented

- Automatic expiration
- Abandoned session handling
- Expiration refresh logic
- Expired route protection

### Timeout

```text
5 minutes
```

---

## 10. Single Active Kiosk Session Lock

### Implemented

- Only one active kiosk session
- Kiosk locking
- Automatic unlock after completion
- Unlock on expiration

### Files

```text
app/Services/KioskSessionLock.php
```

---

## 11. File Validation Hardening

### Implemented

- MIME validation
- Extension validation
- Dangerous filename blocking
- Macro document blocking
- Suspicious PDF detection
- Resource protection

### Files

```text
app/Services/FileValidationService.php
app/Services/PdfValidationService.php
app/Services/ImageValidationService.php
```

---

## 12. Resource Limits

### Implemented

- Max PDF pages
- Image resolution limits
- Office file limits
- Upload size limits
- Page selection limits

### Security Focus

- Anti-memory exhaustion
- Anti-DoS
- Anti-resource abuse

---

## 13. UUID Route Security

### Implemented

- UUID route binding
- Hidden print job identifiers
- Non-enumerable routes

### Example

```text
/preview/uuid
```

Instead of:

```text
/preview/1
```

---

## 14. Signed Temporary Preview URLs

### Implemented

- Expiring preview links
- Replay attack prevention
- Preview tampering protection

Uses Laravel signed URLs.

---

## 15. Queue Manipulation Protection

### Implemented

- Strict state transitions
- Invalid transition blocking
- Duplicate print prevention
- Protected queue flow

### Files

```text
app/Services/PrintJobStateService.php
```

---

## 16. Browser Escape Hardening

### Implemented

- Right-click blocking
- Drag/drop blocking
- Keyboard shortcut blocking
- Zoom prevention
- Overscroll prevention
- Kiosk interaction locking

### Files

```text
resources/views/kiosk/partials/kiosk-lockdown.blade.php
```

---

## 17. Cleanup System

### Implemented

- Expired file cleanup
- Filtered PDF cleanup
- Cancelled job cleanup

---

## 18. Admin Dashboard

### Implemented

- Recent jobs
- Credits
- Completed jobs
- Failed jobs

Basic operational dashboard available.

---

# Current Security Status

Already protected:

- UUID routes
- Signed preview URLs
- Upload validation
- File size limits
- Resource limits
- Queue state protection
- Kiosk locking
- Session expiration
- Browser interaction hardening
- Preview expiration

---

# Planned Hardware Integration

## Raspberry Pi 4

### Planned

- Raspberry Pi OS
- Chromium kiosk mode
- Supervisor
- Queue workers
- Bluetooth services
- CUPS printing

---

## Touchscreen

### Target Resolution

```text
1024x600
```

Optimized UI already implemented.

---

## Epson Printer

### Planned

- CUPS integration
- Page range printing
- Grayscale/color mode
- Duplex support

---

## Coin Acceptor

### Planned

- ESP32 integration
- Pulse detection
- Local API communication

### Architecture

```text
ESP32
→ local HTTP request
→ Laravel add credit
```

---

## Bluetooth File Transfer

### Planned

- BlueZ
- OBEX
- Auto-import inbox
- Automatic preview opening

### Architecture

```text
Bluetooth transfer
→ inbox folder
→ Laravel import
→ preview
```

---

# Planned Phase 2 Features

## 1. Copies Selector

### Example

```text
Copies: 1 2 3
```

### Pricing Formula

```text
pages × copies × mode
```

---

## 2. Duplex Printing

### Options

- Single-sided
- Duplex long edge
- Duplex short edge

---

## 3. Thumbnail Page Selector

Replace manual page input with:

- Page thumbnails
- Touch selection

---

## 4. Final Payment Summary

### Example

```text
Pages: 8
Copies: 2
Mode: Color
Total: ₱32
```

---

## 5. Printer Status Monitoring

### Need

- Offline detection
- No paper detection
- Low ink detection
- Jam detection

---

## 6. Bluetooth Auto-Import UX

Auto-open preview after Bluetooth transfer.

---

## 7. Thermal Receipt Printing

### Optional

- Transaction receipt
- Print history
- UUID tracking

---

## 8. Advanced Color Detection

### Future

- Detect color pages automatically
- Mixed pricing

---

## 9. PDF.js Viewer

Future replacement for iframe preview.

### Advantages

- Full control
- No download button
- Better thumbnails
- Zoom support

---

# Planned Raspberry Pi Security Hardening

## Browser

### Planned

- Chromium kiosk mode
- Disable dev tools
- Disable task switching
- Disable terminal access
- Disable downloads

---

## OS

### Planned

- Disable USB automount
- Supervisor process recovery
- Automatic reboot recovery
- Kiosk-only account

---

# Operational Rules

## Session Timeout

```text
5 minutes
```

## Completed Job Cleanup

```text
10 minutes
```

## Only One Active User

```text
single active kiosk lock
```

---

# Recommended Future Refactor

```text
app/Kiosk/
├── Printing/
├── Security/
├── Uploads/
├── Bluetooth/
├── Controllers/
└── Services/
```

The current application is large enough to justify modularization.

---

# Current Project Status

### Estimated Completion

```text
~75% software complete
```

## Remaining Major Work

- Raspberry Pi deployment
- CUPS integration
- Coin hardware
- Bluetooth hardware integration
- Production kiosk hardening
- Advanced UX features

The core kiosk architecture is already very advanced.
