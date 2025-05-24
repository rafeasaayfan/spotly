import type { VariantProps } from 'class-variance-authority'
import type { HTMLAttributes } from 'vue'
import { cva } from 'class-variance-authority'

export interface SidebarProps {
  side?: 'left' | 'right' | 'top' | 'bottom'
  variant?: 'sidebar' | 'floating' | 'inset'
  collapsible?: 'offcanvas' | 'icon' | 'none'
  class?: HTMLAttributes['class']
}

export { default as Sidebar } from './Sidebar.vue'
export { default as SidebarContent } from './main/SidebarContent.vue'
export { default as SidebarSection } from './main/SidebarSection.vue'
export { default as SidebarFooter } from './SidebarFooter.vue'
export { default as SidebarGroup } from './group/SidebarGroup.vue'
export { default as SidebarGroupAction } from './group/SidebarGroupAction.vue'
export { default as SidebarGroupContent } from './group/SidebarGroupContent.vue'
export { default as SidebarGroupLabel } from './group/SidebarGroupLabel.vue'
export { default as SidebarHeader } from './SidebarHeader.vue'
export { default as SidebarInput } from './SidebarInput.vue'
export { default as SidebarInset } from './SidebarInset.vue'
export { default as SidebarMenu } from './menu/SidebarMenu.vue'
export { default as SidebarMenuAction } from './menu/SidebarMenuOnHover.vue'
export { default as SidebarMenuBadge } from './menu/SidebarMenuBadge.vue'
export { default as SidebarMenuButton } from './menu/buttons/SidebarMenuButton.vue'
export { default as SidebarMenuItem } from './menu/SidebarMenuItem.vue'
export { default as SidebarMenuSkeleton } from './SidebarMenuSkeleton.vue'
export { default as SidebarMenuSub } from './menu/sub/SidebarMenuSub.vue'
export { default as SidebarMenuSubButton } from './menu/sub/SidebarMenuSubButton.vue'
export { default as SidebarMenuSubItem } from './menu/sub/SidebarMenuSubItem.vue'
export { default as SidebarProvider } from './SidebarProvider.vue'
export { default as SidebarRail } from './SidebarRail.vue'
export { default as SidebarSeparator } from './SidebarSeparator.vue'
export { default as SidebarTrigger } from './SidebarTrigger.vue'

export { useSidebar } from './utils'

export const sidebarMenuButtonVariants = cva(
  'peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-sm outline-hidden ring-sidebar-ring transition-[width,height,padding] disabled:pointer-events-none disabled:opacity-50 group-has-data-[sidebar=menu-action]/menu-item:pr-8 aria-disabled:pointer-events-none aria-disabled:opacity-50 group-data-[collapsible=icon]:size-8! group-data-[collapsible=icon]:pr-2! [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0',
  {
    variants: {
      variant: {
        default: 'bg-content-2 border-muted hover:border text-body',
        outline:
          'bg-background shadow-[0_0_0_1px_hsl(var(--sidebar-border))] hover:bg-sidebar-accent hover:text-sidebar-accent-foreground hover:shadow-[0_0_0_1px_hsl(var(--sidebar-accent))]',
      },
      size: {
        default: 'h-8 text-sm',
        sm: 'h-7 text-xs',
        lg: 'h-15 text-sm group-data-[collapsible=icon]:p-0!',
      },
      active: {
        default: 'bg-content-2-active text-body-active font-semibold border border-primary',
        childActive: 'bg-transparent text-active-link font-semibold',
      }
    },
    defaultVariants: {
      variant: 'default',
      size: 'default',
    },
  },
)

export type SidebarMenuButtonVariants = VariantProps<typeof sidebarMenuButtonVariants>
