import{u as p,r as c,o as r,h as f,j as _,d as o,a as e,g as h}from"./app.ca5d1c04.js";const u={class:"grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"},v={class:"col-span-1"},m={class:"col-span-1"},y={class:"col-span-1"},b={class:"col-span-1"},C={class:"col-span-1"},U={name:"ConfigAsset"},w=Object.assign(U,{setup($){const i=p(),a=s=>h("assets."+s).value,t=async()=>{await i.dispatch("config/get",!1)},n=async()=>{await i.dispatch("config/get",!1)};return(s,R)=>{const d=c("CardHeader"),l=c("ImageUpload"),g=c("ConfigPage");return r(),f(g,null,{default:_(()=>[o(d,{first:"",title:s.$trans("config.asset.asset_config"),description:s.$trans("config.asset.asset_info")},null,8,["title","description"]),e("div",u,[e("div",v,[o(l,{label:s.$trans("config.asset.logo"),src:a("logo"),"upload-path":"config/assets?type=logo","remove-path":"config/assets?type=logo",onUploaded:t,onRemoved:n},null,8,["label","src"])]),e("div",m,[o(l,{label:s.$trans("config.asset.logo_light"),src:a("logoLight"),dark:"","upload-path":"config/assets?type=logo_light","remove-path":"config/assets?type=logo_light",onUploaded:t,onRemoved:n},null,8,["label","src"])]),e("div",y,[o(l,{label:s.$trans("config.asset.icon"),src:a("icon"),"upload-path":"config/assets?type=icon","remove-path":"config/assets?type=icon",onUploaded:t,onRemoved:n},null,8,["label","src"])]),e("div",b,[o(l,{label:s.$trans("config.asset.icon_light"),src:a("iconLight"),dark:"","upload-path":"config/assets?type=icon_light","remove-path":"config/assets?type=icon_light",onUploaded:t,onRemoved:n},null,8,["label","src"])]),e("div",C,[o(l,{label:s.$trans("config.asset.favicon"),src:a("favicon"),"upload-path":"config/assets?type=favicon","remove-path":"config/assets?type=favicon",onUploaded:t,onRemoved:n},null,8,["label","src"])])])]),_:1})}}});export{w as default};
